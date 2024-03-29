USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_usuariosmant_eliminar]
@pusuarioid                     NVARCHAR(50)                -- id del usuario
                
AS          
BEGIN
	SET NOCOUNT ON;

	DECLARE @error                             INT
	DECLARE @mensaje      VARCHAR(100)

	IF EXISTS(SELECT RutUsuario FROM Firmantes WHERE RutUsuario = @pusuarioid)     
	BEGIN
		DELETE FROM Firmantes WHERE RutUsuario = @pusuarioid
	END

	IF EXISTS(SELECT RutUsuario FROM FirmantesCentroCosto WHERE rutusuario = @pusuarioid)     
	BEGIN
		DELETE FROM FirmantesCentroCosto WHERE RutUsuario = @pusuarioid
	END

	IF EXISTS(SELECT usuarioid FROM usuarios WHERE usuarioid= @pusuarioid)     
		BEGIN

			DELETE FROM usuarios WHERE usuarioid = @pusuarioid

			SELECT @mensaje = ''
			SELECT @error = 0          
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Información a eliminar ya no existe'
			SELECT @error = 1
		END 
		
	IF EXISTS(SELECT personaid FROM personas WHERE personaid = @pusuarioid)     
		BEGIN

			UPDATE personas SET Eliminado = 1 WHERE personaid = @pusuarioid

			SELECT @mensaje = ''
			SELECT @error = 0          
		END
	                     

	SELECT @error AS error, @mensaje AS mensaje;
END
GO
