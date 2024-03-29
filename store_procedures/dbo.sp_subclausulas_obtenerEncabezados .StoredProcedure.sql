USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_subclausulas_obtenerEncabezados ]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10-04-2019
-- Descripcion: Obtener los datos variables de la tabla de subclausulas
-- Ejemplo:exec sp_subclausulas_obtener '161-1',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_subclausulas_obtenerEncabezados ]

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	
	SELECT TOP 1
		SC.idSubClausula,
		SC.idTipoSubClausula,
		SC.Titulo,
		SC.Descripcion,
		SC.SubClausula,
		TSC.Descripcion As TipoSubClausula
	FROM 
		SubClausulas SC
		INNER JOIN TipoSubClausulas TSC ON TSC.idTipoSubClausula = SC.idTipoSubClausula
	
END
GO
