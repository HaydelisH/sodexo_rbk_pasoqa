USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadocivil_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/04/2019
-- Descripcion:  Lista los estados civiles disponibles 
-- Ejemplo:exec sp_estadocivil_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadocivil_obtener]
	@idestadocivil INT
AS
BEGIN

	SET NOCOUNT ON;	
	
   SELECT	
		idEstadoCivil,
		Descripcion
	FROM
		EstadoCivil
   WHERE
		idEstadoCivil = @idestadocivil  
                       
    RETURN                                                             

END
GO
