USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 06/06/2018
-- Descripcion: Listado de los flujos de firmas
-- Ejemplo:exec sp_flujofirma_listado 
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_listado]
	
AS
BEGIN
	
	
	SELECT 
		idWF,
		NombreWF,
		DiasMax,
		Eliminado
	FROM WorkflowProceso 
	WHERE Eliminado = 0
	
	                         
	                                            
	
END
GO
