USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujos_obtenerNombreWF]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene el nombre del Flujo
-- Ejemplo:exec sp_flujos_obtenerNombreWFc 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujos_obtenerNombreWF]
	@idFlujo INT
AS
BEGIN

     SELECT NombreWF FROM WorkflowProceso
	 WHERE idWF = @idFlujo AND Eliminado=0
                         
    RETURN                                                             

END
GO
