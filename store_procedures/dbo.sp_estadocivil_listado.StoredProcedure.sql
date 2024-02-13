USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadocivil_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/04/2019
-- Descripcion:  Lista los estados civiles disponibles 
-- Ejemplo:exec sp_estadocivil_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadocivil_listado]
AS
BEGIN
	
   SELECT	
		idEstadoCivil,
		Descripcion
	FROM
		EstadoCivil
                         
    RETURN                                                             

END
GO
