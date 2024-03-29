USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadocontratos_listado_misdocumentos]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 24/07/2018
-- Descripcion: Obtiene listado de los estados de los contratos
-- Ejemplo:exec sp_estadocontratos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadocontratos_listado_misdocumentos]
AS
BEGIN
    SELECT idEstado,Descripcion
    FROM ContratosEstados
    WHERE 
		idEstado IN (3,6)
	ORDER BY 
		Descripcion
                         
    RETURN                                                             

END
GO
