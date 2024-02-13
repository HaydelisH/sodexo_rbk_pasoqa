USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantescentrocosto_listar2]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantescentrocosto_listar2]
	@rutempresa VARCHAR(10),
    @rutusuario VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;
    
	BEGIN
        SELECT 
            FirmantesCentroCosto.centrocostoid,
            centroscosto.nombrecentrocosto,
            FirmantesCentroCosto.pordefecto,
            FirmantesCentroCosto.RutUsuario,
            FirmantesCentroCosto.RutEmpresa
        FROM centroscosto
        INNER JOIN FirmantesCentroCosto
            ON FirmantesCentroCosto.centrocostoid = centroscosto.centrocostoid
            AND FirmantesCentroCosto.RutEmpresa = centroscosto.empresaid
            AND FirmantesCentroCosto.RutEmpresa = @rutempresa
            AND FirmantesCentroCosto.RutUsuario = @rutusuario;
    END;
END;
GO
