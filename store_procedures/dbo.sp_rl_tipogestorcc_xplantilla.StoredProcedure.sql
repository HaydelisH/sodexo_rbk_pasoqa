USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_tipogestorcc_xplantilla]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: CSB
-- Creado el: 24/09/2022
-- Descripcion:  lista centro de costo por plantilla
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_tipogestorcc_xplantilla
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_tipogestorcc_xplantilla]
@idplantilla INT,
@rutEmpresa VARCHAR(10)
AS
BEGIN
	/*DECLARE @idtipogestor	INT
	DECLARE @cantidad		INT

	SET @idtipogestor = 0

	SELECT @idtipogestor = idTipoGestor
	FROM Plantillas WHERE idPlantilla = @idplantilla

    IF @idtipogestor > 0 
	BEGIN
		SELECT @cantidad = count(*)  FROM rl_TipoGestorCC as TCC WHERE TCC.idTipoGestor = @idtipogestor
		
		IF @cantidad > 0
		BEGIN
			SELECT
			CC.centrocostoid,
			CC.nombrecentrocosto
			FROM rl_TipoGestorCC as TCC
			LEFT JOIN centroscosto AS CC ON CC.centrocostoid = TCC.centrocostoid
			WHERE TCC.idTipoGestor = @idtipogestor
            GROUP BY CC.centrocostoid,
			CC.nombrecentrocosto
			RETURN
		END
	END

	SELECT 
	CC.centrocostoid,
	CC.nombrecentrocosto
	FROM
	centroscosto as CC
    GROUP BY CC.centrocostoid,
    CC.nombrecentrocosto*/

	SELECT 
		CC.centrocostoid,
		CC.nombrecentrocosto
	FROM
		centroscosto as CC
	WHERE CC.lugarpagoid = 'LP_RLS' AND CC.empresaid = @rutEmpresa
    GROUP BY 
		CC.centrocostoid,
    	CC.nombrecentrocosto		                         
    RETURN                                                             

END
GO
