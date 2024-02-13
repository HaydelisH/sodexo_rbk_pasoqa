USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_graba_empresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion: crea permiso para empresa según id del usuario
-- Ejemplo:exec sp_accesoxusuario_graba_empresa '11111111-1','33333333-3'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_graba_empresa]
@pnewusuarioid		NVARCHAR (50),			-- id del tipo de usuario
@pempresaid			NVARCHAR (10)	-- id de la empresa
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF NOT EXISTS(SELECT usuarioid FROM accesoxusuarioempresas WHERE usuarioid= @pnewusuarioid AND empresaid= @pempresaid) 
		BEGIN 
			INSERT INTO accesoxusuarioempresas
			(usuarioid,empresaid)
			VALUES
			(@pnewusuarioid,@pempresaid);
			
			
		END
	ELSE
	    BEGIN
			SELECT @error= 0
			SELECT @mensaje = ''		    
	    END
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
