USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_listado_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
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
CREATE PROCEDURE [dbo].[sp_lugarespago_listado_20210920]
	@pempresaid NVARCHAR (14) --id empresA
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	lugarpagoid,
	nombrelugarpago,
	"'" + lugarpagoid + "'" as lp
	FROM lugarespago
	WHERE empresaid = @pempresaid
	
	
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
ALTER PROCEDURE [dbo].[sp_lugarespago_listado]
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
