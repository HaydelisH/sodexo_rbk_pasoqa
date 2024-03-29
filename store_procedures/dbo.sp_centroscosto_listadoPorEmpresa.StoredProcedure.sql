USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_listadoPorEmpresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centrocosto_gestor_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_listadoPorEmpresa]
	@pRutEmpresa VARCHAR(10),
	@plugarpagoid NVARCHAR(14),
	--@pdepartamentoid NVARCHAR(14),
	@ptipousuarioid INT,
    @RL_LUGARPAGO_DEFECTO NVARCHAR(14) -- Filtro para discriminar entre RRLL y RRHH
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
		cc.centrocostoid ,
		REPLACE(REPLACE(REPLACE(REPLACE(REPLACE( cc.nombrecentrocosto , 'á', 'a'), 'é','e'), 'í', 'i'), 'ó', 'o'), 'ú','u') as nombrecentrocosto,
		cc.centrocostoid as idCentroCosto,
		REPLACE(REPLACE(REPLACE(REPLACE(REPLACE( cc.nombrecentrocosto , 'á', 'a'), 'é','e'), 'í', 'i'), 'ó', 'o'), 'ú','u') as nombre 
	FROM 
		centroscosto cc
	WHERE
		cc.empresaid = @pRutEmpresa AND
		cc.lugarpagoid = @plugarpagoid --AND 
		--cc.departamentoid = @pdepartamentoid
        AND cc.lugarpagoid != @RL_LUGARPAGO_DEFECTO
	GROUP BY cc.centrocostoid , cc.nombrecentrocosto 
		
END
GO
