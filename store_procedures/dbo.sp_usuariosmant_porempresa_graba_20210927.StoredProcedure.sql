USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_porempresa_graba_20210927]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 03/04/2019
-- Descripcion: Agregar un usuario  
-- Ejemplo:exec sp_usuariosmant_porempresa_graba 'agregar','nombre ejemplo','nacionalid','Soltero(a)','direccion ejemplo','comuna ejemplo','ciudad ejemplo','correo@hotmail.com','22604213-K','18629109-3','2' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_usuariosmant_porempresa_graba_20210927]
	@pAccion CHAR (60),
	@RutUsuario VARCHAR (10),
	@nombre VARCHAR (110),
	@appaterno VARCHAR(50),
	@apmaterno VARCHAR(50),
	@correo VARCHAR(60),
	@nombreusuario VARCHAR(50),
	@clave VARCHAR(100),
	@idFirma INT,
	@loginExterno VARCHAR(1),
	@tipousuarioid INT,
	@rolid INT,
	@RutEmpresa VARCHAR(10),
	@pTipoCorreo INT,
	@centrocostoid VARCHAR(14)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT
	DECLARE @tipoC		INT; --Tipo de correo 0 - 1

	SET @tipoC= 1	
	SET @RutUsuario = UPPER(@RutUsuario)
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT personaid FROM personas WHERE personaid = @RutUsuario)
			BEGIN				
				INSERT INTO personas(
					personaid,
					nombre,
					appaterno,
					apmaterno,
					correo,
					Eliminado
	            )
				VALUES(
					@RutUsuario,
					@nombre,
					@appaterno,
					@apmaterno,
					@correo,
					0
				)
				SELECT @lmensaje = ''
				SELECT @error = 0
			END
		ELSE
		  BEGIN	
			   UPDATE personas 
			   SET
					personaid = @RutUsuario,
					nombre = @nombre,
					appaterno = @appaterno,
					apmaterno = @apmaterno,
					correo = @correo, 
					Eliminado = 0
			   WHERE 
					personaid = @RutUsuario
				
				SELECT @lmensaje = ''
				SELECT @error = 0
		  END
				
		IF NOT EXISTS (SELECT usuarioid FROM usuarios WHERE usuarioid = @RutUsuario )
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
					tipousuarioid, 
					RutEmpresa,
					rolid,
                    centrocostoid
				)
				VALUES (
					@RutUsuario, 
					@nombreusuario,
					@clave,
					GETDATE(),
					1, --Activo por defecto
					0,
					0,
					@idFirma,
					@loginExterno,
					@tipousuarioid,
					@RutEmpresa,
					@rolid,
                    @centrocostoid
				)
				
				--Envio de notificacion al correo 
				INSERT INTO EnvioCorreos(CodCorreo,RutUsuario, TipoCorreo) VALUES(@pTipoCorreo,@RutUsuario,@tipoC)
				
				SELECT @lmensaje = ''
				SELECT @error = 0
			 END 
		ELSE
			BEGIN
				IF EXISTS ( SELECT usuarioid FROM usuarios WHERE usuarioid = @RutUsuario AND bloqueado = 1 )
					BEGIN 
						SELECT @lmensaje = 'Este usuario esta bloqueado, contancte al Administrador'
						SELECT @error = 1
					END 
				ELSE
					BEGIN 
						UPDATE usuarios SET 
							usuarioid = @RutUsuario,
							nombreusuario = @nombreusuario,
							clave = @clave,
							ultimavez = GETDATE(),
							estado = 1, --Activo por defecto
							bloqueado = 0, 
							cambiarclave = 0, 
							idFirma = @idFirma,
							loginExterno = @loginExterno,
							tipousuarioid = @tipousuarioid,
							RutEmpresa = @RutEmpresa,
							rolid = @rolid
						WHERE 
							usuarioid = @RutUsuario
							
						SELECT @lmensaje = ''
						SELECT @error = 0
					END		
			END
						
    END 
    IF (@pAccion='modificar')  
		IF EXISTS (SELECT personaid FROM personas WHERE personaid = @RutUsuario)
		  BEGIN	
			   UPDATE personas 
			   SET
					personaid = @RutUsuario,
					nombre = @nombre,
					appaterno = @appaterno,
					apmaterno = @apmaterno,
					correo = @correo, 
					Eliminado = 0
			   WHERE 
					personaid = @RutUsuario
				
				SELECT @lmensaje = ''
				SELECT @error = 0
		  END
		 IF EXISTS (SELECT usuarioid FROM usuarios WHERE usuarioid = @RutUsuario )
			BEGIN
				IF EXISTS ( SELECT usuarioid FROM usuarios WHERE usuarioid = @RutUsuario AND bloqueado = 1 )
					BEGIN 
						SELECT @lmensaje = 'Este usuario esta bloqueado, contancte al Administrador'
						SELECT @error = 1
					END 
				ELSE
					BEGIN 
						UPDATE usuarios SET 
							usuarioid = @RutUsuario,
							nombreusuario = @nombreusuario,
							--clave = @clave,
							ultimavez = GETDATE(),
							estado = 1, --Activo por defecto
							bloqueado = 0, 
							cambiarclave = 0, 
							idFirma = @idFirma,
							loginExterno = @loginExterno,
							tipousuarioid = @tipousuarioid,
							RutEmpresa = @RutEmpresa,
							centrocostoid = @centrocostoid,
							rolid = @rolid
						WHERE 
							usuarioid = @RutUsuario
							
						SELECT @lmensaje = ''
						SELECT @error = 0
					END		
			END
		
END
GO
