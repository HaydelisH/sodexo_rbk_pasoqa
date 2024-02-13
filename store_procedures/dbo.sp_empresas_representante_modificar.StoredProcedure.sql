USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representante_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Modifica el representante a escoger 
-- Ejemplo:exec sp_empresas_representante_modificar 'modificar','Macarena Parra Bruna','nacional','Soltero(a)','direccion','comuna ','ciudad ','correo2@hotmail.com','22604213-K','18629109-3','3' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_representante_modificar]
	@pAccion CHAR (60),
	@nacionalidad VARCHAR(20),
	@nombre VARCHAR (110),
	@appaterno VARCHAR (50),
	@apmaterno VARCHAR (50),
	@correo VARCHAR(60),
	@RutEmpresa VARCHAR(10),
	@RutUsuario VARCHAR (10),
	@idFirma INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
	  IF EXISTS (SELECT personaid FROM personas WHERE personaid = @RutUsuario AND Eliminado = 0)
       BEGIN
	        UPDATE personas 
	        SET
	        nacionalidad = @nacionalidad,
		    nombre = @nombre,
			appaterno = @appaterno,
			apmaterno = @apmaterno,
            correo = @correo
	        WHERE personaid = @RutUsuario
	      
	        UPDATE usuarios
	        SET
	        idFirma = @idFirma
	        WHERE usuarioid = @RutUsuario 

	        SELECT @lmensaje = ''
			SELECT @error = 0
		
	  END
	END	
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
