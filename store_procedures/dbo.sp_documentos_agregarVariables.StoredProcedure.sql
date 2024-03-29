USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarVariables]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Agrega un Documento nuevo
-- Ejemplo:exec sp_documentos_agregarVariables 1,1,2018,'xxxxxxxxx-x','xxx','xxxxxxxxx-x','xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregarVariables]
	@idDocumento INT,
	@Dia INT,
	@Mes VARCHAR(50),
	@Anno INT,
	@RutEmpresa VARCHAR(10),
	@NombreEmpresa VARCHAR(50),
	@RutCliente VARCHAR(10),
	@NombreCliente VARCHAR(50),
	@FormaPago VARCHAR(50),
	@Equipamiento VARCHAR(MAX),
	@Deducibles VARCHAR(MAX),
	@Porcentaje INT,
	@NombreDoc VARCHAR(50),
	@RutRepresentante_1 VARCHAR(10),
	@Representante_1 VARCHAR(50),
	@RutRepresentante_2 VARCHAR(10),
	@Representante_2 VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
	INSERT INTO DocumentosVariables (idDocumento,Dia, Mes, Anno, RutEmpresa, NombreEmpresa, RutCliente, NombreCliente, FormaPago, Equipamiento, Deducibles, Porcentaje, NombreDoc, RutRepresentante_1, Representante_1, RutRepresentante_2, Representante_2) 
	VALUES (@idDocumento,@Dia, @Mes, @Anno, @RutEmpresa, @NombreEmpresa, @RutCliente, @NombreCliente, @FormaPago, @Equipamiento, @Deducibles, @Porcentaje, @NombreDoc, @RutRepresentante_1, @Representante_1, @RutRepresentante_2, @Representante_2)
	
END
GO
