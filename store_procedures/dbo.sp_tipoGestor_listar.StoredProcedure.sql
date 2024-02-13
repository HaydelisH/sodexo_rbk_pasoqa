USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipoGestor_listar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_tipoGestor_listar]
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
        SELECT 
            TipoGestor.idTipoGestor,
            TipoGestor.Nombre
        FROM TipoGestor;
    END;
END;
GO
