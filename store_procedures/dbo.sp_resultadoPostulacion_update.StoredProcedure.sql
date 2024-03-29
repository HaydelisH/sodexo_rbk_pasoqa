USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_resultadoPostulacion_update]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 26/06/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_resultadoPostulacion_update 'agregar',1,1,1,1,'2018-06-26',1,1,'xxxxxxxx-x',1,'30 Dias',10,1,1,''
-- =============================================
CREATE PROCEDURE [dbo].[sp_resultadoPostulacion_update]
    @rut VARCHAR(10),
    @estadoPostulacion INT,    
    @RutEmpresa VARCHAR(10),
    @idCargoEmpleado VARCHAR(14),
    @estadoPostulante INT,
    @postulacionid INT,
    @postulanteid INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
    DECLARE @blackList INT

    BEGIN TRAN

    BEGIN TRY
            SELECT @blackList = blackList
            FROM Postulantes 
            WHERE rut = @rut;

            UPDATE postulantes
            SET 
                estadoPostulanteid = @estadoPostulante
            WHERE postulanteid = @postulanteid;

            UPDATE Postulaciones
            SET
                estadoPostulacionid = @estadoPostulacion
            WHERE postulacionid = @postulacionid;

            IF (@estadoPostulacion = 2 AND (@blackList != 1 OR @blackList IS NULL))
                BEGIN
                    INSERT INTO EnvioCorreos 
                        (documentoid, CodCorreo, RutUsuario, TipoCorreo)
                    VALUES
                        (@postulacionid, 21, @rut, 3);
                END
        COMMIT TRAN
	END TRY
    BEGIN CATCH
        SELECT @mensaje = 'Hubo un error al intentar cargar los resultados, favor intentar mas tarde'
        SELECT @error = 1
    END CATCH
    IF @error <> 0
        BEGIN
            ROLLBACK TRAN
        END
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
