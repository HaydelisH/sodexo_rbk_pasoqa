USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_firmantes_agregar_x_proveedor]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernández 
-- Creado el: 11/10/2019
-- Descripcion: Listado Cliente
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_proveedores_firmantes_agregar 'agregar','Venezolana ','Haydelis Hernandez','','','hhernandez@rubrika.cl','16611603-1','26131316-2','3','',1,4,99
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_firmantes_agregar_x_proveedor]	
	@pAccion CHAR (60),
	@nacionalidad VARCHAR(20),
	@nombre VARCHAR (110),
	@appaterno VARCHAR(50),
	@apmaterno VARCHAR(50),
	@correo VARCHAR(60),
	@RutProveedor VARCHAR(10),
	@RutUsuario VARCHAR (10),
	@idFirma INT,
	@clave VARCHAR(100),
	@idCargo INT, 
	@prolid INT, 
	@pTipoUsuario INT,
	@pTipoCorreo INT,
	@Cargo VARCHAR(100)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
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
				UPPER(@RutUsuario),
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
		  			
		--USUARIOS
		--marcamos con 1 notifnuevousuario, nos sirve par saber si le notificamos como nuevo usuario cuano¿do tenga el primer
		--documento para firmar
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
					RutEmpresa,
					notifnuevousuario
				)
				VALUES (
					UPPER(@RutUsuario), 
					'',
					@clave,
					GETDATE(),
					1, --Activo por defecto
					0,
					1,
					@idFirma,
					0,
					@pTipoUsuario,
					@prolid,
					@RutProveedor,
					1 -- csb 26-05-2021 marca para que despues le notifique crear nuevo usuario
				)				
			END
			
			--Consulto si existe como Firmante		
			IF  NOT EXISTS (SELECT RutUsuario FROM rl_Firmantes_Proveedores WHERE RutUsuario = @RutUsuario  AND RutProveedor = @RutProveedor)
				BEGIN
					INSERT INTO rl_Firmantes_Proveedores 
					(
						RutUsuario,
						RutProveedor,
						tienerol,
						idCargo,
						cargo
					)
					VALUES(
						UPPER(@RutUsuario),
						@RutProveedor,
						0,
						@idCargo,
						@Cargo
					)
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
			ELSE
			   BEGIN
				   UPDATE rl_Firmantes_Proveedores 
				   SET
					RutUsuario = UPPER(@RutUsuario),
					RutProveedor = @RutProveedor,
					idCargo = @idCargo,
					cargo = @Cargo
				   WHERE RutUsuario= @RutUsuario AND RutProveedor = @RutProveedor 
				END   		
    END 

END
GO
