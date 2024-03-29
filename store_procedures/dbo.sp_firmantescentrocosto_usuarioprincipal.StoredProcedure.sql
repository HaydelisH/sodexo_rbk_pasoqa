USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantescentrocosto_usuarioprincipal]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantescentrocosto_usuarioprincipal]
	@rutempresa VARCHAR(10),
    @centrocostoid VARCHAR(14),
    @principal BIT
AS
BEGIN
	SET NOCOUNT ON;
    
	BEGIN
        SELECT 
            personas.personaid,
            personas.nombre,
            personas.appaterno,
            personas.apmaterno
        FROM FirmantesCentroCosto
        INNER JOIN Firmantes
            ON Firmantes.RutEmpresa = FirmantesCentroCosto.RutEmpresa
            AND Firmantes.RutUsuario = FirmantesCentroCosto.RutUsuario
            AND FirmantesCentroCosto.centrocostoid = @centrocostoid
            AND FirmantesCentroCosto.pordefecto = @principal
            AND FirmantesCentroCosto.RutEmpresa = @rutempresa
        INNER JOIN personas
            ON personas.personaid = Firmantes.RutUsuario;
    END;
END;
GO
