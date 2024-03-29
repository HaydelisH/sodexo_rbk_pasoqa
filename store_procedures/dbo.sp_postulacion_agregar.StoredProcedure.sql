USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_postulacion_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 26/06/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_postulacion_agregar 'agregar',1,1,1,1,'2018-06-26',1,1,'xxxxxxxx-x',1,'30 Dias',10,1,1,''
-- =============================================
CREATE PROCEDURE [dbo].[sp_postulacion_agregar]
    @centrocostoid VARCHAR(14),
    @idCargoEmpleado VARCHAR(14),
    @personaid VARCHAR(10),
    @nombre VARCHAR(110),
    @email VARCHAR(60),
    @telefono VARCHAR(20),
    @Observacion VARCHAR(300),
    @RutEmpresa VARCHAR(10),
    @fechaPostulacion DATE,
    @discapacidad VARCHAR(300),
    @disponibilidadid INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
    DECLARE @postulanteid INT
    DECLARE @discapacitado INT
    DECLARE @postulacionid INT

    BEGIN TRAN

    IF (@discapacidad IS NOT NULL AND @discapacidad != '')
        BEGIN
            SET @discapacitado = 1
        END
    ELSE
        BEGIN
            SET @discapacitado = NULL
        END
    --END
    BEGIN TRY
        IF EXISTS (SELECT postulanteid FROM Postulantes WHERE rut = @personaid)
            BEGIN
                SELECT @postulanteid = postulanteid FROM Postulantes WHERE rut = @personaid
                UPDATE Postulantes SET 
                    rut = @personaid,
                    nombre = @nombre,
                    email = @email,
                    telefono = @telefono,
                    Observacion = @Observacion,
                    estadoPostulanteid = 1,
                    discapacidad = @discapacidad,
                    discapacitado = @discapacitado,
                    contratado = 0,
                    disponibilidadid = @disponibilidadid
                WHERE
                    postulanteid = @postulanteid;
            END
        ELSE
            BEGIN
                INSERT INTO Postulantes
                    (rut, nombre, email, telefono, observacion, estadoPostulanteid, contratado, discapacidad, discapacitado, disponibilidadid)
                VALUES
                    (@personaid, @nombre, @email, @telefono, @Observacion, 1, 0, @discapacidad, @discapacitado, @disponibilidadid);
                SELECT @postulanteid = scope_identity();
            END
        INSERT INTO Postulaciones
            (postulanteid, RutEmpresa, centrocostoid, fechaPostulacion, estadoPostulacionid, idCargoEmpleado)
        VALUES
            (@postulanteid, @RutEmpresa, @centrocostoid, @fechaPostulacion, 1, @idCargoEmpleado);
        SELECT @postulacionid = scope_identity();
        INSERT INTO EnvioCorreos 
            (documentoid, CodCorreo, RutUsuario, TipoCorreo)
        VALUES
            (@postulacionid, 20, @personaid, 2);
        COMMIT TRAN
	END TRY
    BEGIN CATCH
        SELECT @mensaje = 'Hubo un error al intentar generar la postulacion, favor intentar mas tarde'
        SELECT @error = 1
    END CATCH
    IF @error <> 0
        BEGIN
            ROLLBACK TRAN
        END
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
