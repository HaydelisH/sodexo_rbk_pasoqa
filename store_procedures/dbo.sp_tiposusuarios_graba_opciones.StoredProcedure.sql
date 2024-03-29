USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_graba_opciones]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 2/09/2016
-- Descripcion:	modifica, o agrega opciones por tipo de usuario 
-- Ejemplo:exec sp_tiposusuarios_graba_opciones 'modificar',1,2,0,0,0,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_graba_opciones]
@pAccion 			CHAR(60),		-- accion agregar o modificar
@ptipousuarioid		INT,			-- id del tipo de usuario
@pnombre			NVARCHAR(50),	-- nombre del tipo de usuario
@popcionid			NVARCHAR(50),	-- id opcion menu
@pconsulta			INT,			-- marca si usuario consulta 1=SI
@pmodifica			INT,			-- marca si usuario modifica 1=SI
@pcrea				INT,			-- marca si usuario crea 1=SI
@pelimina			INT,			-- marca si usuario elimina 1=SI
@pver				INT				-- marca si usuario puede ver documentos 1=SI
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error			INT
	DECLARE @mensaje		VARCHAR(100)
	DECLARE @tipousuarioid INT
		
	IF (@pAccion='agregar') 
	BEGIN
		SELECT @tipousuarioid = tipousuarioid 
		FROM tiposusuarios WHERE nombre = @pnombre
		
		IF NOT EXISTS(SELECT tipousuarioid FROM opcionesxtipousuario WHERE tipousuarioid= @tipousuarioid AND opcionid=@popcionid) 
			BEGIN 
				INSERT INTO opcionesxtipousuario
				(tipousuarioid,opcionid,consulta,modifica,crea,elimina,ver)
				VALUES
				(@tipousuarioid,@popcionid,@pconsulta,@pmodifica,@pcrea,@pelimina,@pver);
				
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
		IF EXISTS(SELECT tipousuarioid FROM opcionesxtipousuario WHERE tipousuarioid= @ptipousuarioid AND opcionid=@popcionid) 
			BEGIN 
			
				IF (@pconsulta = 0 AND @pmodifica = 0 AND @pcrea = 0 AND @pelimina = 0 AND @pver = 0)		
					BEGIN
						DELETE opcionesxtipousuario 
						WHERE tipousuarioid= @ptipousuarioid 
						AND opcionid=@popcionid
					END	
				ELSE
					BEGIN
						UPDATE opcionesxtipousuario 
						SET 
						consulta = @pconsulta,
						modifica = @pmodifica,
						crea	 = @pcrea,
						elimina  = @pelimina,
						ver		 = @pver
						WHERE tipousuarioid= @ptipousuarioid 
						AND opcionid=@popcionid
					END
			
			END
		ELSE
			BEGIN
				IF (@pconsulta = 1 OR @pmodifica = 1 OR @pcrea = 1 OR @pelimina = 1 OR @pver = 1)
					BEGIN		
						INSERT INTO opcionesxtipousuario
						(tipousuarioid,opcionid,consulta,modifica,crea,elimina,ver)
						VALUES
						(@ptipousuarioid,@popcionid,@pconsulta,@pmodifica,@pcrea,@pelimina,@pver);
					END
			END			

			SELECT @error= 0
			SELECT @mensaje = ''	
	END		
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
