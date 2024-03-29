USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
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
CREATE PROCEDURE [dbo].[sp_lugarespago_listado]
	@pempresaid NVARCHAR (14), --id empresA
    @RL_LUGARPAGO_DEFECTO NVARCHAR(14) -- Filtro para discriminar entre RRLL y RRHH
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	lugarpagoid,
	nombrelugarpago,
	"'" + lugarpagoid + "'" as lp
	FROM lugarespago
	WHERE empresaid = @pempresaid
	AND lugarespago.lugarpagoid != @RL_LUGARPAGO_DEFECTO
	order by nombrelugarpago
	
	RETURN;
END

GO
