USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_personas_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Genera todos los registros
-- =============================================
CREATE PROCEDURE [dbo].[sp_personas_listado]
      @RutEmpresa VARCHAR (10)
AS   
BEGIN

IF EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa)
	BEGIN
    SELECT personas.personaid , personas.nombre + '' + personas.appaterno + '' + personas.apmaterno AS nombrecompleto FROM personas  
    INNER JOIN Firmantes ON personas.personaid = Firmantes.RutUsuario
    WHERE Firmantes.RutEmpresa = @RutEmpresa
    RETURN 
    
    END                                                            

END
GO
