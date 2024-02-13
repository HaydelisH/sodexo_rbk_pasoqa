USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
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
CREATE PROCEDURE [dbo].[sp_lugarespago_obtener]
@pempresaid NVARCHAR (14), --id empresa
@plugarpagoid NVARCHAR (14) --id lugar pago
	
AS	
BEGIN
	SET NOCOUNT ON;
		SELECT 
		lugarpagoid,
		nombrelugarpago
		FROM lugarespago
		WHERE lugarpagoid = @plugarpagoid
		AND	empresaid = @pempresaid
	
	
	RETURN;
END
GO
