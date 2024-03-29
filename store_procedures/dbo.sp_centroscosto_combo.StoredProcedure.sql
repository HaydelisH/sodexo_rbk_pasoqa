USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_combo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 30/06/2011
-- Descripcion:	Obtiene lugar de pago
-- Modificado: gdiaz 03/02/2021
-- Ejemplo:exec sp_lugarespago_obtener '55555555-5','lp1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_combo]
	@pempresaid NVARCHAR (10), --id empresA
	@lugarpagoid NVARCHAR (14) --
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	centrocostoid AS idCentroCosto,
	nombrecentrocosto
	FROM centroscosto
	WHERE empresaid = @pempresaid
        And lugarpagoid = @lugarpagoid
	
	
	RETURN;
END

/****** Object:  StoredProcedure [dbo].[sp_lugarespago_obtener]    Script Date: 10/08/2019 00:03:09 ******/
SET ANSI_NULLS OFF


/*
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 30/06/2011
-- Descripcion:	Obtiene lugar de pago
-- Ejemplo:exec sp_lugarespago_obtener '55555555-5','lp1'
-- =============================================
ALTER PROCEDURE [dbo].[sp_centroscosto_combo]
	@pempresaid NVARCHAR (14) --id empresA
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	centrocostoid,
	nombrecentrocosto,
	"'" + centrocostoid + "'" as lp
	FROM centroscosto
	WHERE empresaid = @pempresaid
	
	
	RETURN;
END
*/
GO
