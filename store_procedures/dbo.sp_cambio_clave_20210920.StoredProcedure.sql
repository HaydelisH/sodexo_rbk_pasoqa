USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cambio_clave_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
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

CREATE PROCEDURE [dbo].[sp_cambio_clave_20210920]
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
			IF (@pclavenew = @pclaveant)	
			BEGIN
				SELECT @lmensaje = 'Error: Contraseña nueva debe ser distinta a la actual'
				SELECT @error = 1
			END
			ELSE
			BEGIN
				UPDATE usuarios SET clave =@pclavenew, cambiarclave=0
				WHERE usuarioid = @pusuarioid AND clave = @pclaveant

				SELECT @lmensaje = ''
				SELECT @error = 0		
			END
		END
	END


	SELECT @error AS error, @lmensaje AS mensaje
END
GO
