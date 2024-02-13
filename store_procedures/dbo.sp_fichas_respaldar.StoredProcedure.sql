USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_respaldar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 29-05-2019
-- Descripcion: Respaldar una ficha antes de eliminar
-- Ejemplo:exec sp_fichas_eliminar
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_respaldar]
	@fichaid		INT ,
	@usuarioid nvarchar(10)
AS	
BEGIN
	SET NOCOUNT ON;	
	
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)
	
	IF  NOT EXISTS ( SELECT fichaid FROM fichaselim WHERE fichaid = @fichaid) 
		BEGIN
			--Respaldar las fichas 
			INSERT INTO fichaselim ( fichaid, empleadoid, empresaid, centrocostoid, fechasolicitud, estadoid, fechaeliminacion, usuarioid)
			SELECT fichaid, empleadoid, empresaid, centrocostoid, fechasolicitud, estadoid, GETDATE(), @usuarioid FROM fichas WHERE fichaid = @fichaid
			
			SET @error = 0
			SET @mensaje = '' 
		END
	ELSE
		BEGIN 
			SET @error = 1
			SET @mensaje = 'Esta ficha ya fue eliminada' 
		END		
	
	SELECT @error AS error, @mensaje AS mensaje; 
	
	RETURN;
END
GO
