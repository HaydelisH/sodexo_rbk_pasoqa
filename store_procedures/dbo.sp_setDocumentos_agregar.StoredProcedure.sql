USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_setDocumentos_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--sp_setDocumentos_agregar '76012833-3','22',33,1
CREATE PROCEDURE [dbo].[sp_setDocumentos_agregar]
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
        IF EXISTS (SELECT idPlantilla FROM PlantillasEmpresa WHERE idPlantilla = @idPlantilla AND RutEmpresa = @RutEmpresa ) 
        BEGIN
            BEGIN TRANSACTION;
                INSERT INTO setDocumentos
                    (idTipoMovimiento,RutEmpresa,idCargoEmpleado,idPlantilla)
                VALUES
                    (@idTipoMovimiento,@RutEmpresa,@idCargoEmpleado,@idPlantilla);
            COMMIT TRANSACTION;
        END;
		ELSE
			BEGIN
				SELECT @mensaje = 'La Plantilla elegida no existe.';
				SELECT @error = 1;
			END;
    END;
    SELECT @error AS error, @mensaje AS mensaje;
END;
GO
