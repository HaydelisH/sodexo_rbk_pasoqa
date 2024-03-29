USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuarios]    Script Date: 1/25/2024 11:06:33 AM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_usuarios]
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
      DECLARE @intentosLogin INT
      DECLARE @intentosLoginMax INT
      DECLARE @deshabilitado INT
      DECLARE @error          INT
      DECLARE @mensaje        VARCHAR(100)
	DECLARE @topeInactividad		INT;

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
				DECLARE @nDays INT
				SELECT @nDays = parametro FROM Parametros WHERE idparametro = 'bloqueoCambioPass'
			
				UPDATE usuarios
				SET bloqueado = 1
				WHERE
					DATEDIFF(DAY, dateChangePass, GETDATE()) > @nDays
					AND cambiarclave = 1 -- Solo bloquear si la opción cambiarclave es 1
					AND usuarioid = @pusuarioid

                  IF (@pclave = '') OR (@pclave IS NULL) 
                  BEGIN
                             SELECT  @mensaje = 'Debe ingresar contraseña'
                             SELECT @error = 1                  
                  END
                  ELSE
                  BEGIN 
                        SELECT @topeInactividad = parametro FROM Parametros WHERE idparametro = 'topeInactividad'
						DECLARE @dayToPass INT
						SELECT @dayToPass = parametro FROM Parametros WHERE idparametro = 'cambioContraseña'
                        UPDATE usuarios SET deshabilitado = 1
                        WHERE 
                              usuarioid IN ( 
                                    (SELECT 
                                          CASE 
                                                WHEN DATEDIFF(day, usuarios.ultimavez, GETDATE()) > @topeInactividad
                                                THEN usuarios.usuarioid 
                                                ELSE null
                                          END
                                    FROM usuarios
                                    WHERE usuarios.usuarioid = @pusuarioid)
                              );

                        SELECT @xclave = clave, @xcambiarclave= cambiarclave,@xbloqueado= bloqueado, @intentosLogin=intentosLogin, @deshabilitado=deshabilitado  FROM usuarios WHERE usuarioid=@pusuarioid
                        IF (@deshabilitado <> 1)
                        BEGIN
                              IF (@pclave <> @xclave)
                                    BEGIN 
                                          IF @intentosLogin IS NULL
                                                UPDATE usuarios SET intentosLogin = 1 WHERE usuarioid=@pusuarioid
                                          ELSE
                                                UPDATE usuarios SET intentosLogin =  intentosLogin + 1 WHERE usuarioid=@pusuarioid
                                          -- SELECT  @mensaje = 'Contraseña incorrecta'
                                          SELECT @intentosLogin=intentosLogin FROM usuarios WHERE usuarioid=@pusuarioid
                                          SELECT @intentosLoginMax = parametro FROM Parametros WHERE idparametro = 'intentosLogin'
                                          IF (@intentosLogin >= @intentosLoginMax)
                                                BEGIN
                                                      UPDATE usuarios SET bloqueado = 1 WHERE usuarioid=@pusuarioid
                                                     -- INSERT INTO EnvioCorreos(CodCorreo, RutUsuario, TipoCorreo) VALUES(16, @pusuarioid, 1)	

                                                      SELECT  @mensaje = 'Usuario bloqueado'
                                                      SELECT @error = 1                  
                                                END
                                          ELSE
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
                                    ELSE IF (@xcambiarclave=1) 
                                          BEGIN
                                                SELECT  @mensaje = 'Debe cambiar su contraseña para continuar'
                                                SELECT @error = 2 
                                          END
                                    --ELSE
                                    --      BEGIN
                                    --            UPDATE usuarios SET intentosLogin = NULL WHERE usuarioid=@pusuarioid
                                    --      END
									ELSE
										BEGIN							
											 DECLARE @userId NVARCHAR(11) 
											 SELECT TOP 1 @userId = 
																CASE 
																	WHEN DATEDIFF(day, updatePass, GETDATE()) > @dayToPass
																	THEN userId 
																	ELSE null
																END
														FROM historyPass
														WHERE userId= @pusuarioid
														ORDER BY id DESC

											SELECT @userId as mensaje
											 UPDATE usuarios SET cambiarclave = 1, dateChangePass =  GETDATE()
											 WHERE 
													usuarioid IN ( 
														@userId
													);
											UPDATE usuarios SET intentosLogin = NULL WHERE usuarioid=@pusuarioid
										END
                              END
                        END
                        ELSE
                              BEGIN
                                    SELECT @mensaje = 'Cuenta deshabilitada'
                                    SELECT @error = 1                  
                              END
                  END
            END 
      END
      
      
      SELECT @error AS error, @mensaje AS mensaje;
END

GO
