USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_agregarInfoContacto]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 11/06/2019
-- Descripcion: Agregar datos de empleados con Usuario
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec sp_empleados_agregarInfoContacto
-- =============================================
--NUEVO
CREATE PROCEDURE [dbo].[sp_empleados_agregarInfoContacto]
	@idFormulario INT,
	@ppersonaid VARCHAR (10),
	@pdireccion VARCHAR(110),
	@pciudad VARCHAR(20),
	@pcomuna VARCHAR(30),
	@celularContacto VARCHAR(20),
	@celularPersonal VARCHAR(20),
	@envioinfo INT,
	@nombreContacto VARCHAR(110),
	@relacionContacto VARCHAR(100),
    @correoNotificacionPorConcentimiento VARCHAR(60)
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT

	BEGIN TRANSACTION 
	BEGIN TRY
	
		--PERSONAS	
		IF NOT EXISTS ( SELECT personaid FROM personaInfoContacto WHERE personaid = @ppersonaid ) 
			BEGIN 
				INSERT INTO personaInfoContacto(
						personaid, 
						direccion, 
						ciudad, 
						comuna,
                        celularContacto,
                        celularPersonal,
                        envioinfo,
                        nombreContacto,
                        relacionContacto,
                        correoNotificacionPorConcentimiento
					)VALUES(
						@ppersonaid,
						@pdireccion,
						@pciudad,
						@pcomuna,
						@celularContacto,
						@celularPersonal,
                        @envioinfo,
                        @nombreContacto,
                        @relacionContacto,
                        @correoNotificacionPorConcentimiento
					)
				SELECT @lmensaje = ''
				SELECT @error = 0			
			END  
		ELSE
			BEGIN 
                IF @idFormulario = 1
                    BEGIN
                        UPDATE personaInfoContacto SET 
                            correoNotificacionPorConcentimiento = @correoNotificacionPorConcentimiento
                        WHERE 
                            personaid = @ppersonaid
                    END
			END
		
		/*--EMPLEADOS
		IF NOT EXISTS (SELECT empleadoid FROM Empleados WHERE empleadoid = @ppersonaid  )      
			BEGIN 
				INSERT INTO Empleados(empleadoid, rolid ) VALUES(@ppersonaid, @prolid)
								
				SELECT @lmensaje = ''
				SELECT @error = 0
			END
		ELSE
			BEGIN
				UPDATE Empleados SET 
					rolid = @prolid
				WHERE 
					empleadoid = @ppersonaid
					
				SELECT @lmensaje = ''
				SELECT @error = 0
			END*/
			
		/*--USUARIOS
		IF NOT EXISTS (SELECT usuarioid FROM usuarios WHERE usuarioid = @ppersonaid  )
			BEGIN 
				INSERT INTO usuarios(
					usuarioid, 
					nombreusuario,
					clave,
					ultimavez,
					estado,
					bloqueado, 
					cambiarclave, 
					idFirma,
					loginExterno,
					tipousuarioid
				)
				VALUES (
					@ppersonaid , 
					'',
					@clave,
					GETDATE(),
					1, --Activo por defecto
					0,
					0,
					@idFirma,
					0,
					@tipo
				)
				
				SET @num = 1	
			END
			
			IF( @num = 1 )
				BEGIN
					--Enviar el correo 
					INSERT INTO EnvioCorreos(CodCorreo, RutUsuario, TipoCorreo) VALUES(@codcorreo, @ppersonaid, @tipocorreo)	
				END*/
							 
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
		
		SET @error		= ERROR_NUMBER()
		SET @lmensaje	= ERROR_MESSAGE()
			
	END CATCH
	
	SELECT @error AS error, @lmensaje AS mensaje
	
END
GO
