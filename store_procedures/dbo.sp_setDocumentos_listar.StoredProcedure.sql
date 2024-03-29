USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_setDocumentos_listar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_setDocumentos_listar]
	@RutEmpresa VARCHAR(10),
    @idCargoEmpleado NVARCHAR(14)
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN

        SELECT
            TipoMovimiento.idTipoMovimiento,
            TipoMovimiento.Descripcion AS nombreTipoMovimento,
            Plantillas.idPlantilla,
            Plantillas.Descripcion_Pl AS nombrePlantilla,
            setDocumentos.RutEmpresa,
            setDocumentos.idCargoEmpleado
			
        FROM setDocumentos
        INNER JOIN TipoMovimiento
            ON TipoMovimiento.idTipoMovimiento = setDocumentos.idTipoMovimiento
            AND setDocumentos.RutEmpresa = @RutEmpresa
            AND setDocumentos.idCargoEmpleado = @idCargoEmpleado
        INNER JOIN Plantillas
            ON Plantillas.idPlantilla = setDocumentos.idPlantilla
            AND Plantillas.Eliminado = 0
    END;
END;
GO
