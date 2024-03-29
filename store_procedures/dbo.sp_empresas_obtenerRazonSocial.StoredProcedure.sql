USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_obtenerRazonSocial]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Obtiene el nombre de la Empresa
-- Ejemplo:exec sp_empresas_obtenerRazonSocial
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_obtenerRazonSocial]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	
	DECLARE @TipoEmpresa INT
	DECLARE @RazonSocial VARCHAR(50)
	DECLARE @Rut VARCHAR(10);
	
	--Consulto los datos que necesito 
     SELECT RutEmpresa,RazonSocial,Direccion,Comuna,Ciudad FROM Empresas
	 WHERE RutEmpresa = @RutEmpresa AND Eliminado=0
                         
    RETURN                                                            

END
GO
