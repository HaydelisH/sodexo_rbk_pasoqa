USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_firmantes_listar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichas_firmantes_listar]
	@fichaid INT
AS
BEGIN
	SET NOCOUNT ON;
    
	BEGIN
        DECLARE @idTipoMovimiento INT;
        DECLARE @centrocosto VARCHAR(14);
        SELECT
            @idTipoMovimiento = TipoMovimiento,
            @centrocosto = CodDivPersonal
        FROM fichasDatosImportacion
        WHERE fichasDatosImportacion.fichaid = @fichaid;
        
        DECLARE @RutEmpresa VARCHAR(10);
        SELECT @RutEmpresa = empresaid FROM centroscosto WHERE centroscosto.centrocostoid = @centrocosto;

        SELECT
            personas.personaid,
            personas.nombre as nombreFirmante,
			personas.appaterno,
			personas.apmaterno,
			isnull(personas.nombre,'') + ' ' + isnull(personas.appaterno,'') + ' ' + isnull(personas.apmaterno,'') as nombre,
            Cargos.Descripcion,
            CASE WHEN FirmantesCentroCosto.pordefecto = 1 THEN 'checked'
            ELSE '' END AS pordefecto
        FROM Firmantes
        INNER JOIN Cargos
            ON Cargos.idCargo = Firmantes.idCargo
            AND Cargos.Eliminado = 0
        INNER JOIN personas
            ON personas.personaid = Firmantes.RutUsuario
        INNER JOIN FirmantesCentroCosto
            ON FirmantesCentroCosto.RutUsuario = Firmantes.RutUsuario
            AND FirmantesCentroCosto.RutEmpresa = Firmantes.RutEmpresa
        INNER JOIN fichasDatosImportacion
            ON Firmantes.RutEmpresa = @RutEmpresa --fichasDatosImportacion.RutEmpresa = Firmantes.RutEmpresa
            AND fichasDatosImportacion.CodDivPersonal = FirmantesCentroCosto.centrocostoid
            AND fichasDatosImportacion.TipoMovimiento = @idTipoMovimiento
            AND fichasDatosImportacion.fichaid = @fichaid
		ORDER BY pordefecto DESC;
    END;
END;
GO
