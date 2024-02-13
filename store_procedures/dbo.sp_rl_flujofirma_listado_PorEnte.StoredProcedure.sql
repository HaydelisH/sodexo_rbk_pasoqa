USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_flujofirma_listado_PorEnte]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 21/10/2018
-- Descripcion: Listado de los flujos de firmas por ente 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_flujofirma_listado_PorEnte 
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_flujofirma_listado_PorEnte]
	
AS
BEGIN
	
	
	SELECT 
	idWF,
	NombreWF,
	DiasMax,
	Eliminado
	FROM WorkflowProceso 
	WHERE Eliminado = 0 AND tipoWF = 1

	                         
	                                            
	
END
GO
