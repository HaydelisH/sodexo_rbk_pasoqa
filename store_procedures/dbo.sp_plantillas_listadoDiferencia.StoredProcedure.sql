USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_listadoDiferencia]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05-05-2019
-- Descripcion: Obtiene las Plantillas que no tiene essa empresa
-- Ejemplo:exec sp_plantillas_listadoDiferencia 'xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_listadoDiferencia]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	
    SELECT
		P.idPlantilla, 
		P.Titulo_Pl,
		P.Descripcion_Pl,
		WP.NombreWF,
		TD.NombreTipoDoc,
		C.Titulo,
		P.Aprobado
	FROM
	plantillas P
		left join plantillasempresa PE on P.idPlantilla = PE.idPlantilla
		inner join TipoDocumentos TD on P.idTipoDoc = TD.idTipoDoc
		inner join Categorias C on P.idCategoria = C.idCategoria
		inner join WorkflowProceso WP on p.idWF = WP.idWF
		where 
		P.Eliminado = 0 AND
		( RutEmpresa != @RutEmpresa or PE.idPlantilla is null ) AND
		P.idPlantilla not in (select idPlantilla from PlantillasEmpresa where RutEmpresa = @RutEmpresa)
	GROUP BY 
		P.idPlantilla, 
		P.Titulo_Pl,
		P.Descripcion_Pl,
		WP.NombreWF,
		TD.NombreTipoDoc,
		C.Titulo,
		P.Aprobado

    RETURN                                                             

END
GO
