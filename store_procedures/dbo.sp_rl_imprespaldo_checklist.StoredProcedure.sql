USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_imprespaldo_checklist]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 25/08/2020
-- Descripcion: listado checklist
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_imprespaldo_checklist 1,10000
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_imprespaldo_checklist]
@iddocumento numeric (18,0),
@idplantilla int

AS	
BEGIN
	SET NOCOUNT ON;

	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)	
	
	SELECT 
	CH.idTipoMovimiento as idplantilla,
	CH.idTipoGestor,
	CH.Obligatorio,
	IMP.nombre as nombrearchivo,
	IMP.idrespaldo,
	IMP.iddocumento,
	TG.Nombre as nombretipodoc,
	@iddocumento as iddocumento
	FROM rl_ChecklistDocumentos AS CH
	INNER JOIN TipoGestor AS TG ON TG.idTipoGestor = CH.idTipoGestor
	LEFT JOIN rl_imprespaldodoc AS IMP ON IMP.iddocumento = @iddocumento
									AND IMP.idtipogestor = CH.idTipoGestor
	WHERE CH.idTipoMovimiento = @idplantilla
	order by CH.Obligatorio desc
	
	RETURN
	 
END
GO
