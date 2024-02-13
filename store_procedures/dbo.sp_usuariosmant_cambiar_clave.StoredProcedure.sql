USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_cambiar_clave]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_usuariosmant_cambiar_clave]
@pusuarioid                     NVARCHAR(50),              -- id del usuario
@pclave                                             NVARCHAR(100)             -- clave
                
AS          
BEGIN
    SET NOCOUNT ON;

    DECLARE @error                             INT
    DECLARE @mensaje      VARCHAR(100)
                    

    IF EXISTS(SELECT usuarioid FROM usuarios WHERE usuarioid= @pusuarioid)     
        BEGIN
				--IF NOT EXISTS(SELECT pass FROM (
				--		SELECT TOP 3 pass FROM historyPass where userId = @pusuarioid
				--	) TMP
				--	WHERE pass = @pclave
				--)
				--BEGIN
				--	UPDATE usuarios SET clave = @pclave, bloqueado = 0, cambiarclave = 1, intentosLogin = NULL WHERE usuarioid = @pusuarioid
				
				--	INSERT INTO historyPass(userid,pass,updatePass)values(@pusuarioid,@pclave,GETDATE())
				--	SELECT @mensaje = ''
				--	SELECT @error = 0 
				--END
				--ELSE
				--BEGIN
				--	SELECT @mensaje = 'La contraseña no puede haber sido usada recientemente'
				--	SELECT @error = 1
				--END	
            UPDATE usuarios SET clave = @pclave, bloqueado = 0, cambiarclave = 1, intentosLogin = NULL WHERE usuarioid = @pusuarioid
                                                            
            SELECT @mensaje = ''
            SELECT @error = 0          
        END
    ELSE
        BEGIN
            SELECT @mensaje = 'Información a cambiar ya no existe'
            SELECT @error = 1
        END                       
    

                                    
    SELECT @error AS error, @mensaje AS mensaje;
END
GO
