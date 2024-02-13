USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_checkListDocumentos_listar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_checkListDocumentos_listar]
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
        SELECT 
            ChecklistDocumentos.idTipoMovimiento,
            ChecklistDocumentos.idTipoGestor,
            TipoMovimiento.Descripcion,
            TipoGestor.Nombre,
            ChecklistDocumentos.Obligatorio
        FROM ChecklistDocumentos
        INNER JOIN TipoMovimiento
            ON TipoMovimiento.idTipoMovimiento = ChecklistDocumentos.idTipoMovimiento
        INNER JOIN TipoGestor
            ON TipoGestor.idTipoGestor = ChecklistDocumentos.idTipoGestor;
    END;
END;
GO
