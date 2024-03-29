USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerAval]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 21/08/2018
-- Descripcion: Verificar si el flujo tiene Aval
-- Ejemplo:sp_documentos_obtenerAval 9
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerAval]
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
			COUNT(NombreWF) AS Aval
		FROM 
			WorkflowProceso 
		WHERE 
			idWF = @idWF
			AND 
			( NombreWF LIKE '%'+ 'Aval' 
			OR 
			  NombreWF LIKE 'Aval' + '%' 
			OR 
			  NombreWF LIKE '%' + 'Aval' + '%')		   
	END 
END
GO
