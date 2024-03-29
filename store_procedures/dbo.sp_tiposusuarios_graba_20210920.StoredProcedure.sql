USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_graba_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Cristian Soto
-- Creado el: 1/09/2016
-- Descripcion:	modifica, o crea tipos de usuario 
-- Ejemplo:exec sp_tiposusuarios_graba 'modificar',1,'xxxx',30
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_graba_20210920]
@pAccion 			CHAR(60),		-- accion agregar o modificar
@ptipousuarioid		INT,			-- id del tipo de usuario
@pnombre			NVARCHAR(50),	-- nombre
@pdiasinactividad	NVARCHAR(50),	-- dias inactividad
@prolprivado		INT,			-- rol privado 1=si puede ver
@pestado			INT				-- estado 1=si puede ver finiquitados
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		
	IF (@pAccion='agregar') 
	BEGIN
		IF NOT EXISTS(SELECT tipousuarioid FROM tiposusuarios WHERE  nombre = @pnombre) 
			BEGIN 
				INSERT INTO tiposusuarios
				(nombre,diasinactividad,rolid,estado)
				VALUES
				(@pnombre,@pdiasinactividad,@prolprivado,@pestado);
				
				SELECT @error= 0
				SELECT @mensaje = ''				
			END
		ELSE
			BEGIN
				SELECT @mensaje = 'los datos ingresados ya fueron creados anteriormente'
				SELECT @error = 1			
			END			

	END	
	
	IF (@pAccion='modificar') 
	BEGIN
		IF EXISTS(SELECT nombre FROM tiposusuarios WHERE  nombre = @pnombre AND tipousuarioid <> @ptipousuarioid ) 
			BEGIN
				SELECT @mensaje = 'Nombre de Perfil a modificar ya esta creado'
				SELECT @error = 1			
			END 
		ELSE
			BEGIN
				IF EXISTS(SELECT tipousuarioid FROM tiposusuarios WHERE tipousuarioid= @ptipousuarioid) 
					BEGIN 
						UPDATE tiposusuarios 
						SET 
						nombre = @pnombre,
						diasinactividad = @pdiasinactividad,
						rolid = @prolprivado,
						estado = @pestado
						WHERE tipousuarioid= @ptipousuarioid 
					
						SELECT @error= 0
						SELECT @mensaje = ''				
					END
				ELSE
					BEGIN
						SELECT @mensaje = 'La información a modificar no existe'
						SELECT @error = 1			
					END	
			END	

	END		
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
