USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_obtener2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centrocosto_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_obtener2]
	@empresaid  varchar(14),
	@lugarpagoid varchar(14),
	@centrocostoid varchar(14)
	
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
		cc.centrocostoid As idCentroCosto,
		cc.nombrecentrocosto As nombreCentroCosto,
		cc.lugarpagoid,
		lp.nombrelugarpago,
		e.RutEmpresa,
		e.RazonSocial
	FROM centroscosto cc
	INNER JOIN lugarespago lp ON cc.lugarpagoid = lp.lugarpagoid AND LP.empresaid = @empresaid
		INNER JOIN Empresas e ON lp.empresaid = e.RutEmpresa 
	WHERE cc.centrocostoid = @centrocostoid 
	AND CC.lugarpagoid = @lugarpagoid 
	and CC.empresaid = @empresaid
		
END
GO
