USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantescentrocosto_listar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantescentrocosto_listar]
	@rutempresa VARCHAR(10),
    @rutusuario VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;
    
	BEGIN
        SELECT 
            centroscosto.centrocostoid,
            centroscosto.nombrecentrocosto
        FROM centroscosto
        WHERE 
            centroscosto.empresaid = @rutempresa
            AND centroscosto.centrocostoid NOT IN (
                SELECT
                    FirmantesCentroCosto.centrocostoid
                FROM FirmantesCentroCosto
                WHERE
                    FirmantesCentroCosto.RutUsuario = @rutusuario
            );
    END;
END;
GO
