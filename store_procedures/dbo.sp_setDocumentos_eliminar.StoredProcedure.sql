USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_setDocumentos_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_setDocumentos_eliminar]
	@RutEmpresa VARCHAR(10),
   	@idCargoEmpleado VARCHAR(14),
    @idPlantilla INT,
    @idTipoMovimiento INT
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @error		INTEGER;
	DECLARE @mensaje	VARCHAR(100);	
    SET @mensaje = '';
    SET @error = 0;

 	BEGIN
        BEGIN TRANSACTION;
            DELETE FROM setDocumentos WHERE 
                RutEmpresa = @RutEmpresa 
                AND idTipoMovimiento = @idTipoMovimiento
                AND idCargoEmpleado = @idCargoEmpleado
                AND idPlantilla = @idPlantilla
        COMMIT TRANSACTION;
    END;
    SELECT @error AS error, @mensaje AS mensaje;
END;
GO
