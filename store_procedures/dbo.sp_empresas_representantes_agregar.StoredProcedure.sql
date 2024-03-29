USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representantes_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
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
CREATE PROCEDURE [dbo].[sp_empresas_representantes_agregar]
	@pAccion CHAR (60),
	@nacionalidad VARCHAR (20),
	@nombre VARCHAR (110),
	@appaterno VARCHAR(50),
	@apmaterno VARCHAR(50),
	@correo VARCHAR(60),
	@RutEmpresa VARCHAR(10),
	@RutUsuario VARCHAR (10),
	@idFirma INT
	
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	 VARCHAR(100)
	DECLARE @error		 INT
	DECLARE @total		 INT
	DECLARE @tipoEmpresa INT
	DECLARE @clave		 NVARCHAR(100);

	SET @RutUsuario = UPPER(@RutUsuario)
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT personaid FROM personas WHERE personaid = @RutUsuario)
			BEGIN				
				INSERT INTO personas(
				personaid,
				nacionalidad,
				nombre,
				appaterno,
				apmaterno,
	            correo,
	            Eliminado
	            )
				VALUES(
				@RutUsuario,
				@nacionalidad,
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
		        nacionalidad = @nacionalidad,
		        personaid = @RutUsuario,
			    nombre = @nombre,
				appaterno = @appaterno,
				apmaterno = @apmaterno,
	            correo = @correo
		       WHERE personaid = @RutUsuario
		  END
				
			IF  NOT EXISTS (SELECT RutUsuario FROM Firmantes WHERE RutUsuario = @RutUsuario AND RutEmpresa=@RutEmpresa)
				BEGIN
					INSERT INTO Firmantes 
					(
					RutEmpresa,
					RutUsuario
					)
					VALUES(
					@RutEmpresa,
					@RutUsuario
					)
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
			ELSE
			   BEGIN
					SELECT @lmensaje = 'EL REPRESENTANTE YA EXISTE PARA ESTA EMPRESA'
					SELECT @error = 1
			   END 
			--Verificacion en tabla Usuarios
			IF EXISTS( SELECT usuarioid FROM usuarios WHERE usuarioid = @RutUsuario)
				BEGIN
					UPDATE usuarios SET 
						idFirma = @idFirma
					WHERE usuarioid = @RutUsuario
				END
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
