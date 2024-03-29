USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_agregarConUsuario]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 11/06/2019
-- Descripcion: Agregar datos de empleados con Usuario
-- Ejemplo:exec sp_empleados_agregarConUsuario
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_agregarConUsuario]
	@ppersonaid VARCHAR (10),
	@pnacionalidad VARCHAR(20),
	@pnombre VARCHAR(110),
	@pappaterno VARCHAR(50),
	@papmaterno VARCHAR(50),
	@pcorreo VARCHAR(60),
	@pdireccion VARCHAR(150),
	@pciudad VARCHAR(20),
	@pcomuna VARCHAR(30),
	@pfechanacimiento DATE,
	@pestadocivil INT,
	@prolid INT,
	@clave VARCHAR(100),
	@estado NVARCHAR(1),
	@pTipoCorreo INT,
	@pTipoFirma INT,
	@pTipoUsuario INT
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @tipo       INT
	DECLARE @idFirma	INT
	DECLARE @num		INT
	DECLARE @codcorreo	INT
	DECLARE @tipocorreo INT
	
	DECLARE @claveTemporal VARCHAR(100)
	DECLARE @largo int; -- LARGO MINIMO CONTRASEÑA

	
	SET @num = 0 --Contador de insert 
	SET @tipocorreo = 1 --Tipo correo

	SET @ppersonaid = UPPER(@ppersonaid)
	
	--Roles
	--IF ( @prolid <> 1 ) SET @prolid =  2

	BEGIN TRANSACTION 
	BEGIN TRY
	
		--PERSONAS	
		IF NOT EXISTS ( SELECT personaid FROM personas WHERE personaid = @ppersonaid ) 
			BEGIN 
				INSERT INTO personas(
						personaid, 
						nacionalidad,
						nombre,
						appaterno, 
						apmaterno, 
						correo, 
						direccion, 
						ciudad, 
						comuna,
						fechanacimiento,
						estadocivil,
						Eliminado
					)VALUES(
						@ppersonaid,
						@pnacionalidad,
						@pnombre, 
						@pappaterno, 
						@papmaterno, 
						@pcorreo, 
						@pdireccion,
						@pciudad,
						@pcomuna,
						@pfechanacimiento,
						@pestadocivil,
						0
					)
						
				SELECT @lmensaje = ''
				SELECT @error = 0			
			END  
		ELSE
			BEGIN 
				UPDATE personas SET 
					nombre = @pnombre,
					appaterno = @pappaterno,
					apmaterno = @papmaterno,
					nacionalidad = @pnacionalidad,
					correo = @pcorreo,
					direccion = @pdireccion,
					comuna = @pcomuna,
					ciudad = @pciudad, 
					fechanacimiento = @pfechanacimiento,
					estadocivil = @pestadocivil,
					Eliminado = 0
				WHERE 
					personaid = @ppersonaid
			END      
		
		--EMPLEADOS
		IF NOT EXISTS (SELECT empleadoid FROM Empleados WHERE empleadoid = @ppersonaid  )      
			BEGIN 
				INSERT INTO Empleados(empleadoid, rolid, idEstadoEmpleado ) VALUES(@ppersonaid, @prolid, @estado)
								
				SELECT @lmensaje = ''
				SELECT @error = 0
			END
		ELSE
			BEGIN
				UPDATE Empleados SET 
					rolid = @prolid,
					idEstadoEmpleado = @estado
				WHERE 
					empleadoid = @ppersonaid
					
				SELECT @lmensaje = ''
				SELECT @error = 0
			END
			
		--USUARIOS
		--marcamos con 1 notifnuevousuario, nos sirve par saber si le notificamos como nuevo usuario cuano¿do tenga el primer
		--documento para firmar
		IF NOT EXISTS (SELECT usuarioid FROM usuarios WHERE usuarioid = @ppersonaid  )
			BEGIN 
				
				SELECT  @largo =parametro from Parametros where idparametro='largoClaveMin';
				SELECT @claveTemporal = dbo.fnCustomPass(@largo,'CN');
				
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
					tipousuarioid,
					rolid,
					claveTemporal,
					notifnuevousuario
				)
				VALUES (
					@ppersonaid , 
					'',
					CONVERT(varchar(256),HASHBYTES('SHA2_256',@claveTemporal),2),	
					--@clave,
					GETDATE(),
					1, --Activo por defecto
					0,
					1,
					@pTipoFirma,
					0,
					@pTipoUsuario,
					@prolid,
					@claveTemporal,
					1 -- csb 26-05-2021 marca para que despues le notifique crear nuevo usuario
				)
				
				SET @num = 1	
			END
			
			/*IF( @num = 1 )
				BEGIN
					--Enviar el correo 
					INSERT INTO EnvioCorreos(CodCorreo, RutUsuario, TipoCorreo) VALUES(@pTipoCorreo, @ppersonaid, @tipocorreo)	
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
