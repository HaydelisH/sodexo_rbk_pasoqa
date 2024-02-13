USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- Ejemplo:exec sp_empleados_eliminar 'xxxxxxxxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_eliminar]
	@pempleadoid NVARCHAR(10)	-- identificador del tipo de usuario         
AS          
BEGIN
	SET NOCOUNT ON;
	DECLARE @error INT
	DECLARE @lmensaje VARCHAR(100);
	
	IF EXISTS ( SELECT Rut FROM ContratoDatosVariables WHERE Rut = @pempleadoid )
		BEGIN 
			SELECT @error = 1
			SELECT @lmensaje = 'No puede eliminar el empleado, porque tiene Documentos generados en este sistema'
		END 
	ELSE
		BEGIN 
			IF EXISTS ( SELECT empleadoid FROM Empleados WHERE empleadoid = @pempleadoid ) 
				BEGIN 
					
					BEGIN TRANSACTION 
					BEGIN TRY 
					
						DELETE FROM Empleados WHERE empleadoid = @pempleadoid
						DELETE FROM personas  WHERE personaid  = @pempleadoid 
						DELETE FROM usuarios  WHERE usuarioid  = @pempleadoid
						
						SELECT @error = 0
						SELECT @lmensaje = '' 
						
					COMMIT TRANSACTION
					END TRY

					BEGIN CATCH
					ROLLBACK TRANSACTION 
					
						SET @error		= ERROR_NUMBER()
						SET @lmensaje	= "No se pudo eliminar el empleado, contacte a soporte"
					
					END CATCH
		
				END
			ELSE
				BEGIN 
					SELECT @error = 1
					SELECT @lmensaje = 'El empleado seleccionado no existe'
				END
		END
		
	SELECT @error as error, @lmensaje as mensaje	
    RETURN
END
GO
