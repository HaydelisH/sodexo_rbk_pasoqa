USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_documentos_listar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichas_documentos_listar]
	@fichaid INT
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
        DECLARE @idTipoMovimiento INT;
        DECLARE @centrocosto NVARCHAR(14);
        DECLARE @CodCargo NVARCHAR(14)
        DECLARE @idFichaOrigen INT
        DECLARE @RutEmpresa VARCHAR(10);
        
        SELECT @idFichaOrigen = 2 --Generados 
        
        --Busco los datos necesarios
        SELECT
            @idTipoMovimiento = TipoMovimiento,
            @centrocosto = CodDivPersonal,
            @CodCargo = CodCargo
        FROM fichasDatosImportacion
        WHERE 
			fichasDatosImportacion.fichaid = @fichaid;
     
        --Busco los datos de la empresa
        SELECT @RutEmpresa = empresaid FROM centroscosto WHERE centroscosto.centrocostoid = @centrocosto;
               
		With DocumentosTabla
		as
		(
			SELECT 
				FI.fichaid,FI.CodCargo, FD.documentoid, C.idPlantilla,C.idEstado
			FROM
				fichasDatosImportacion FI
			LEFT JOIN fichasdocumentos FD ON FD.fichaid = FI.fichaid
			LEFT JOIN Contratos C ON FD.documentoid = c.idDocumento
			WHERE 
				FI.fichaid = @fichaid AND FD.idFichaOrigen = @idFichaOrigen
		)
		SELECT 
			@fichaid As fichaid,
			TG.Nombre,
			CASE WHEN DT.documentoid IS NULL THEN 'No generado'
			ELSE 'Generado con el id: ' + CONVERT(VARCHAR(20),DT.documentoid)  END As estado,
			CASE WHEN DT.documentoid IS NULL THEN 0
			ELSE 1 END As estado2,
			ROW_NUMBER()Over(Order by DT.documentoid) As incremental,
			SD.idPlantilla,
			DT.documentoid,
			DT.idEstado
		FROM 
			DocumentosTabla DT
			RIGHT JOIN setDocumentos SD ON DT.idPlantilla = SD.idPlantilla
			RIGHT JOIN Plantillas P ON SD.idPlantilla = P.idPlantilla
			RIGHT JOIN TipoGestor TG ON P.idTipoGestor = TG.idTipoGestor
		WHERE 
			SD.RutEmpresa = @RutEmpresa AND SD.idCargoEmpleado = @CodCargo AND Sd.idTipoMovimiento = @idTipoMovimiento
		Order by
			SD.idPlantilla ASC
        
    END;
END;
GO
