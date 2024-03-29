USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_actualizarLink]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 26/06/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_actualizarLink 'agregar',1,1,1,1,'2018-06-26',1,1,'xxxxxxxx-x',1,'30 Dias',10,1,1,''
-- =============================================
CREATE PROCEDURE [dbo].[sp_actualizarLink]
    @RutEmpresa VARCHAR(10),
    @idCargoEmpleado VARCHAR(14),
    @link VARCHAR(300),
    @fechaCaducidadLink DATE    
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT

    BEGIN TRAN

    BEGIN TRY
        UPDATE CargosEmpresa SET 
            link = @link,
            fechaCaducidadLink = @fechaCaducidadLink
        WHERE
            RutEmpresa = @RutEmpresa
        AND
            idCargoEmpleado = @idCargoEmpleado
        COMMIT TRAN
	END TRY
    BEGIN CATCH
        SELECT @mensaje = 'Hubo un error al intentar grabar el link, favor intentar mas tarde'
        SELECT @error = 1
    END CATCH
    IF @error <> 0
        BEGIN
            ROLLBACK TRAN
        END
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
