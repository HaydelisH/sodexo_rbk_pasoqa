USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_obtenerPorNivel3]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centrocosto_obtener
--sp_centroscosto_obtenerPorEmpresa 'nivel_cc2','nivel_lp1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_obtenerPorNivel3]
	
	@empresaid NVARCHAR(14),
	@lugarpagoid NVARCHAR(14),
	@centrocostoid NVARCHAR(14)
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
		centrocostoid As idCentroCosto,
		nombrecentrocosto As Descripcion
	FROM 
		centroscosto
	WHERE 
		centrocostoid = @centrocostoid 
        AND lugarpagoid = @lugarpagoid
        AND empresaid = @empresaid
		
END
GO
