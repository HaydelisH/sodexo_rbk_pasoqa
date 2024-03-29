USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerNotario]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 03/07/2018
-- Descripcion: Verificar si el flujo tiene Notario
-- Ejemplo:sp_documentos_modificar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerNotario]
	@idWF INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    BEGIN
		--Consultar existe la palabra Notario en el registro 			
		SELECT 
			COUNT(NombreWF) AS Notario
		FROM 
			WorkflowProceso 
		WHERE 
			idWF = @idWF 
			AND 
			( NombreWF LIKE '%'+ 'Notario' 
			OR 
			  NombreWF LIKE 'Notario' + '%' 
			OR 
			  NombreWF LIKE '%' + 'Notario' + '%')			   
	END 
END
GO
