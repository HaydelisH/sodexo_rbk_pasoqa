USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_plantillas_obtenerPlantillasEmpresasTipoFlujo]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene las Plantillas asociadas a una Empresa 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_plantillas_obtenerPlantillasEmpresas 'xxx'
-- [sp_rl_plantillas_obtenerPlantillasEmpresasTipoFlujo] '76602656-7',11,2
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_plantillas_obtenerPlantillasEmpresasTipoFlujo]
	@RutEmpresa VARCHAR (10),
	@idTipoDoc INT,
	@TipoFlujo INT
AS
BEGIN
	
	DECLARE @Flujo VARCHAR(50);
	
/*	IF( @TipoFlujo = 1) SET @Flujo = 'Cliente'
	ELSE SET @Flujo = 'Proveedor'*/
	
    SELECT
		PlantillasEmpresa.idPlantilla,
		Plantillas.Descripcion_Pl,
		CASE Plantillas.Aprobado			
			WHEN 0 THEN 'disabled'
			WHEN 1 THEN ''
		END AS Aprobado
	FROM
		PLantillasEmpresa
	INNER JOIN
		Plantillas
	ON
		Plantillas.idPlantilla= PlantillasEmpresa.idPlantilla
	INNER JOIN
		WorkflowProceso
	ON
		Plantillas.idWF= WorkflowProceso.idWF 
		AND 
		WorkflowProceso.tipoWF = 1
	INNER JOIN 
		TipoDocumentos
	ON 	
		Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
	INNER JOIN 
		Empresas
	ON 	
		PlantillasEmpresa.RutEmpresa = Empresas.RutEmpresa
	INNER JOIN 
		Categorias
	ON
		Plantillas.idCategoria = Categorias.idCategoria
	WHERE	
		PlantillasEmpresa.RutEmpresa = @RutEmpresa
	AND
		Plantillas.Eliminado = 0
	AND		
		Plantillas.idTipoDoc = @idTipoDoc
--	AND 
	--	WorkflowProceso.NombreWF NOT LIKE '%' + @Flujo + '%' 
		
    RETURN                                                             
                                               

END
GO
