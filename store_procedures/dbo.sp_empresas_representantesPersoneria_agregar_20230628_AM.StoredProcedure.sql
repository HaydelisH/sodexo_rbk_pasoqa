USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representantesPersoneria_agregar_20230628_AM]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Agregar representante a una empresa 
-- Ejemplo:exec sp_empresas_representantes_agregar 'agregar','nombre ejemplo','nacionalid','Soltero(a)','direccion ejemplo','comuna ejemplo','ciudad ejemplo','correo@hotmail.com','22604213-K','18629109-3','2' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_representantesPersoneria_agregar_20230628_AM]
	@pAccion CHAR (60),
	@nacionalidad VARCHAR(20),
	@nombre VARCHAR (110),
	@appaterno VARCHAR(50),
	@apmaterno VARCHAR(50),
	@correo VARCHAR(60),
	@RutEmpresa VARCHAR(10),
	@RutUsuario VARCHAR (10),
	@idFirma INT,
	@clave VARCHAR(100),
	@idCargo INT, 
	@prolid INT, 
	@pTipoUsuario INT,
	@pTipoCorreo INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT
	DECLARE @perfil		INT
	DECLARE @cargo      INT
	DECLARE @tipo		INT
	DECLARE @tipocorreo INT;
	
	SET  @tipocorreo = 1

	SET @RutUsuario = UPPER(@RutUsuario)
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		--Consulto si existe en personas
		IF NOT EXISTS (SELECT personaid FROM personas WHERE personaid = @RutUsuario)
			BEGIN				
				INSERT INTO personas(
				personaid,
				nacionalidad,
				nombre,
				appaterno,
				apmaterno,
	            correo,
	            estadocivil,
	            Eliminado
	            )
				VALUES(
				@RutUsuario,
				@nacionalidad,
				@nombre,
				@appaterno,
				@apmaterno,
	            @correo,
	            1,
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
		        nacionalidad = @nacionalidad,
			    nombre = @nombre,
				appaterno = @appaterno,
				apmaterno = @apmaterno,
	            correo = @correo
		       WHERE personaid = @RutUsuario
		  END
		  			
		--Cosnultar si usuario existe 
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
					rolid,
					RutEmpresa
				)
				VALUES (
					@RutUsuario, 
					'',
					@clave,
					GETDATE(),
					1, --Activo por defecto
					0,
					0,
					@idFirma,
					0,
					@pTipoUsuario,
					@prolid,
					@RutEmpresa
				)
				
				--Enviar el correo 
				INSERT INTO EnvioCorreos(CodCorreo, RutUsuario, TipoCorreo) VALUES(@pTipoCorreo, @RutUsuario, @tipocorreo)	
			
			END
			
			--Consulto si existe como Firmante		
			IF  NOT EXISTS (SELECT RutUsuario FROM Firmantes WHERE RutUsuario = @RutUsuario AND RutEmpresa=@RutEmpresa)
				BEGIN
					INSERT INTO Firmantes 
					(
					RutEmpresa,
					RutUsuario,
					tienerol,
					idCargo
					)
					VALUES(
					@RutEmpresa,
					@RutUsuario,
					0,
					@idCargo
					)
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
			ELSE
			   BEGIN
				   UPDATE Firmantes 
				   SET
					RutEmpresa = @RutEmpresa,
					RutUsuario = @RutUsuario,
					idCargo = @idCargo
				   WHERE RutUsuario= @RutUsuario AND RutEmpresa = @RutEmpresa
				END   		
    END 

END
GO
