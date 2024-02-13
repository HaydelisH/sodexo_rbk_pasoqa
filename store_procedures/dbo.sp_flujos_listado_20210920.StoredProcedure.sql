USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujos_listado_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene del listado de los flujos disponibles
-- Ejemplo:exec sp_flujos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujos_listado_20210920]
AS
BEGIN
    SELECT idWF,NombreWF 
    FROM WorkflowProceso
	WHERE Eliminado=0
                         
    RETURN                                                             

END
GO
