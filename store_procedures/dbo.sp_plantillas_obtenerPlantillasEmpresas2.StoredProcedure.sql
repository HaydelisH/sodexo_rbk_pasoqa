USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerPlantillasEmpresas2]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerPlantillasEmpresas2]
	@RutEmpresa VARCHAR(10),
	@idCargoEmpleado NVARCHAR(14),
    @idtipomovimiento INT
AS
BEGIN

    SELECT
 		Plantillas.idPlantilla,
		Plantillas.Descripcion_Pl
	FROM PLantillas
	INNER JOIN PlantillasEmpresa PE 
	    ON PE.idPlantilla = Plantillas.idPlantilla
        AND PE.RutEmpresa = @RutEmpresa
        AND Plantillas.Eliminado = 0
        AND PE.idPlantilla NOT IN(
            SELECT
                Plantillas.idPlantilla
            FROM setDocumentos
            INNER JOIN TipoMovimiento
                ON TipoMovimiento.idTipoMovimiento = setDocumentos.idTipoMovimiento
                AND setDocumentos.idTipoMovimiento = @idtipomovimiento
                AND setDocumentos.RutEmpresa = @RutEmpresa
                AND setDocumentos.idCargoEmpleado = @idCargoEmpleado
            INNER JOIN Plantillas
                ON Plantillas.idPlantilla = setDocumentos.idPlantilla
                AND Plantillas.Eliminado = 0
        );
                         
    RETURN                                                             

END;
GO
