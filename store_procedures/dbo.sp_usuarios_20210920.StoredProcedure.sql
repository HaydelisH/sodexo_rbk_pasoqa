USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuarios_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

CREATE PROCEDURE [dbo].[sp_usuarios_20210920]
@pAccion    CHAR(60),
@pusuarioid NVARCHAR(50),
@pclave     CHAR(100),
@pip        VARCHAR(32),
@psession   CHAR(16),
@pultimavez DATETIME
      
AS    
BEGIN
      SET NOCOUNT ON;

      DECLARE @xclave         CHAR(100)
      DECLARE @cuantos        INT
      DECLARE @xcambiarclave  INT
      DECLARE @xbloqueado          INT
      DECLARE @error          INT
      DECLARE @mensaje        VARCHAR(100)

      IF (@pAccion='agregar') 
      BEGIN
      
            IF NOT EXISTS(SELECT usuarioid FROM usuarios WHERE usuarioid = @pusuarioid) 
                  BEGIN 
                        INSERT INTO  usuarios
                        (usuarioid,clave,ip,sesion,ultimavez)
                        VALUES
                        (@pusuarioid,@pclave,@pip,@psession,@pultimavez);
                        
                        SELECT @error= 0
                        SELECT @mensaje = ''                     
                  END
            ELSE
                  BEGIN
                        SELECT @mensaje = 'El usuario ua fué ingresado'
                        SELECT @error = 1            
                  END               

      END


      IF (@pAccion='modificar') 
      BEGIN
            UPDATE usuarios
            SET usuarioid=@pusuarioid,clave=@pclave,ip=@pip,sesion=@psession,ultimavez=@pultimavez
            WHERE 
            usuarioid=@pusuarioid 

            SELECT @error= 0
            SELECT @mensaje = ''
      END


      IF (@pAccion='eliminar') 
      BEGIN
            DELETE FROM usuarios    WHERE usuarioid=@pusuarioid

            SELECT @error= 0
            SELECT @mensaje = ''
      END


      IF (@pAccion='obtener') 
      BEGIN
            SELECT usuarios.usuarioid AS usuarioid,
            clave,
            ip,
            sesion,
            ultimavez, 
            nombre
            FROM usuarios 
            LEFT JOIN personas ON usuarios.usuarioid=personas.personaid
            WHERE usuarios.usuarioid=@pusuarioid
            
            RETURN
      END

      IF (@pAccion='agregarSesion') 
      BEGIN
            UPDATE usuarios
            SET sesion=@psession,ip=@pip,ultimavez=GETDATE()
            WHERE usuarioid=@pusuarioid
            
            --INSERT INTO visitaweb (usuarioid, fecha) VALUES (@pusuarioid, GETDATE());

            SELECT @error= 0
            SELECT @mensaje = ''
      END   
      

      IF (@pAccion='actualizarSesion') 
      BEGIN
            UPDATE usuarios SET     ultimavez=GETDATE() WHERE sesion=@psession

            SELECT @error= 0
            SELECT @mensaje = ''
      END   


      IF (@pAccion='eliminarSesion') 
      BEGIN
            UPDATE usuarios SET sesion='' WHERE sesion=@psession

            SELECT @error= 0
            SELECT @mensaje = ''
      END   


      IF (@pAccion='verificarSesion') 
      BEGIN
            SELECT
            usuarios.usuarioid AS usuarioid,
            ip,
            ultimavez, 
            ISNULL(personas.nombre,'') + ' ' + ISNULL(personas.appaterno,'') + ' ' + ISNULL(personas.apmaterno,'') AS nombre,
            cambiarclave, 
            bloqueado, 
            correo,
            usuarios.tipousuarioid,
            GETDATE() as fechaactual,
            tiposusuarios.nombre as nombreperfil
            FROM usuarios 
            INNER JOIN personas ON personas.personaid = usuarios.usuarioid
            LEFT JOIN tiposusuarios on usuarios.tipousuarioid = tiposusuarios.tipousuarioid
            WHERE sesion=@psession
			AND usuarios.usuarioid = @pusuarioid
            GROUP BY usuarios.usuarioid, ip, ultimavez, personas.nombre,personas.appaterno,personas.apmaterno ,cambiarclave, bloqueado, correo,usuarios.tipousuarioid,tiposusuarios.nombre
            RETURN
      END   


      IF (@pAccion='obtenerContrasena') 
      BEGIN

            SELECT  @mensaje = ''
            SELECT @error = 0

            IF (NOT EXISTS(SELECT clave FROM usuarios WHERE usuarioid=@pusuarioid))
            BEGIN
                        SELECT  @mensaje = 'Error: Contraseña actual no válida'
                        SELECT @error = 1
            END 
            ELSE 
            BEGIN
                  IF (@pclave = '') OR (@pclave IS NULL) 
                  BEGIN
                             SELECT  @mensaje = 'Debe ingresar contraseña'
                             SELECT @error = 1                  
                  END
                  ELSE
                  BEGIN 
                        SELECT @xclave = clave, @xcambiarclave= cambiarclave,@xbloqueado= bloqueado FROM usuarios WHERE usuarioid=@pusuarioid
                  
                        IF (@pclave <> @xclave)
                        BEGIN 
                            -- SELECT  @mensaje = 'Contraseña incorrecta'
                             SELECT  @mensaje = 'Usuario o Contraseña incorrecta'
                             SELECT @error = 1 
                        END
                        ELSE
                        BEGIN
                             IF (@xbloqueado=1) 
                             BEGIN
                                   SELECT  @mensaje = 'Usuario bloqueado'
                                         SELECT @error = 1                  
                             END
                             ELSE
                             BEGIN
                                   IF (@xcambiarclave=1) 
                                   BEGIN
                                         SELECT  @mensaje = 'Debe cambiar su contraseña para continuar'
                                         SELECT @error = 1 
                                   END
                             END
                        END
                  END
            END 
      END
      
      
      SELECT @error AS error, @mensaje AS mensaje;
END
GO
