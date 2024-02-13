USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cambio_clave]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


-- =============================================
-- Autor: Cristian Soto
-- Creado el: 20/02/2017
-- Descripcion:	Cambio de contraseña.
-- Ejemplo:exec sp_cambio_clave '11111111-1','aaaaa','bbbb','bbbb'
-- =============================================
CREATE PROCEDURE [dbo].[sp_cambio_clave]
@pusuarioid		NVARCHAR(10),
@pclaveant		CHAR (100),
@pclavenew		CHAR (100),
@pclaverep		CHAR (100)

AS

BEGIN
	SET NOCOUNT ON;

	DECLARE @swestado    INT
	DECLARE @lmensaje    VARCHAR(100)
	DECLARE @error		INT

    DECLARE @obligaCambioClave VARCHAR(2)
    SELECT @obligaCambioClave = parametro FROM Parametros WHERE idparametro = 'obligaCambioClave'


	SELECT @lmensaje = ''
	IF (NOT EXISTS (SELECT estado FROM usuarios	 WHERE usuarioid = @pusuarioid AND clave = @pclaveant))
	BEGIN
		SELECT  @lmensaje = 'Error: Contraseña actual no válida'
		SELECT	@error = 1

	END
	ELSE
	BEGIN
		IF (@pclavenew <> @pclaverep)
		BEGIN	
			SELECT @lmensaje = 'Error: Contraseña nueva y confirmación son distintas'
			SELECT @error = 1	
		END
		ELSE
		BEGIN
			IF (@pclavenew = @pclaveant AND @obligaCambioClave = 'si')	
                BEGIN
                    SELECT @lmensaje = 'Error: Contraseña nueva debe ser distinta a la actual'
                    SELECT @error = 1
                END
			ELSE
                BEGIN
					-- IF NOT EXISTS(SELECT pass FROM (
					-- 		SELECT TOP 3 pass FROM historyPass where userId = @pusuarioid
					-- 	) TMP
					-- 	WHERE pass = @pclavenew
					-- )
					-- BEGIN
					-- 	UPDATE usuarios SET clave = @pclavenew,-- bloqueado = 0,
					-- 	cambiarclave = 0,ultimoCambioClave = GETDATE(), intentosLogin = NULL WHERE usuarioid = @pusuarioid
				
					-- 	INSERT INTO historyPass(userid,pass,updatePass)values(@pusuarioid,@pclavenew,GETDATE())
					-- 	SELECT @Lmensaje = ''
					-- 	SELECT @error = 0 
					-- END
					-- ELSE
					-- BEGIN
					-- 	SELECT @Lmensaje = 'La contraseña no puede haber sido usada recientemente'
					-- 	SELECT @error = 1
					-- END	
					
                    UPDATE usuarios SET clave =@pclavenew, cambiarclave=0, ultimoCambioClave = GETDATE()
                    WHERE usuarioid = @pusuarioid AND clave = @pclaveant

                    SELECT @lmensaje = ''
                    SELECT @error = 0		
                END
		END
	END


	SELECT @error AS error, @lmensaje AS mensaje
END
GO
