USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_cambiar_clave_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

CREATE PROCEDURE [dbo].[sp_usuariosmant_cambiar_clave_20210920]
@pusuarioid                     NVARCHAR(50),              -- id del usuario
@pclave                                             NVARCHAR(100)             -- clave
                
AS          
BEGIN
                SET NOCOUNT ON;

               DECLARE @error                             INT
                DECLARE @mensaje      VARCHAR(100)
                               

                IF EXISTS(SELECT usuarioid FROM usuarios WHERE usuarioid= @pusuarioid)     
                               BEGIN
                                               UPDATE usuarios SET clave = @pclave WHERE usuarioid = @pusuarioid
                                                                                              
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
