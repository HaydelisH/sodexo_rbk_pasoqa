USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionesxtipousuario_formulariosUsuario]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Modificado: gdiaz 14/04/2021
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_opcionesxtipousuario_formulariosUsuario] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_opcionesxtipousuario_formulariosUsuario]
	@usuarioid VARCHAR(10),
	@opcionid VARCHAR(50)
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        formularioPlantilla.opcionid,
        formularioPlantilla.idPlantilla,
        empleadoFormulario.idDocumento
    FROM 
	    formularioPlantilla
    INNER JOIN empleadoFormulario ON empleadoFormulario.idFormulario = formularioPlantilla.idFormulario
    WHERE 
        empleadoFormulario.empleadoid = @usuarioid
        AND 
        formularioPlantilla.opcionid = @opcionid
        AND
        empleadoFormulario.estadoFormularioid = 1
	
END
GO
